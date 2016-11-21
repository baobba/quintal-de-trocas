require 'correios-frete'

class ItemsController < ApplicationController
  before_action :set_item, only: [:show, :edit, :update, :destroy, :exchange, :activate]
  before_action :authenticate_user!, except: [:index, :show, :index_near, :exchange, :activate]

  class NotOwner < StandardError
  end

  def index
    @bs_container = false

    @q = Item.for_exc.includes(:item_category, :item_age, :item_images)

    location = if !params[:lat].blank? && !params[:lon].blank?
      [params[:lat], params[:lon]].join(",")
    elsif !params[:city_eq].blank?
      params[:city_eq]
    elsif current_user && !current_user.city.blank?
      [current_user.city, current_user.state].join(",")
    elsif lookup_ip_location && !lookup_ip_location.city.blank?
      "#{lookup_ip_location.city}, #{lookup_ip_location.state}, #{lookup_ip_location.country}"
    else
      "88110-690, Brasil"
    end

    if request.format.json?
      zoom = if params[:zoom].blank?
          params[:q].blank? ? 15 : params[:within] || 15
        else
          case params[:zoom]
            when "15"
              10
            when "14"
              20
            when "13"
              30
            when "12"
              40
            when "11"
              50
            when "10"
              100
            when "9"
              200
            else
              50
          end
        end
    else
      zoom = params[:within] || 1000
    end

    # @q = @q.near(location, zoom, :units => :km, :order => 'distance')
    @q = @q.search(params[:q])

    respond_to do |format|

      format.all {
        @items = @q.result(distinct: true).page params[:page]
      }

      format.js {
        @items = @q.result(distinct: true).page params[:page]
      }

      format.json {

        @items = @q.result(distinct: true).order("created_at DESC")
        # @items = @items.page params[:page] if !params[:tipo].blank?

        @items = @items.map{|f| [f.id, f.title, f.latitude, f.longitude, "<div class='info_content'><p class='lead' style='margin: 5px 0 10px 0;font-size: 16px;line-height: 120%;'><a href='#{item_url(f)}'>#{f.title}</a></p><div style='margin-left:15px;' class='pull-right'><img src='#{f.item_images.first.image.url(:thumb) if f.item_images.first}' class='img-thumbnail' width='80'></div><small class='text-muted'>#{f.description[0..100]} ...</small></div>"]}
        render json: @item
      }
    end

  end

  def show
    @item_images = @item.item_images.all

    respond_to do |format|
      format.html { }
      format.json { render json: @item }
    end
  end


  def exchange
    @exchange = @item.exchanges.new(item_to: @item.user.id)

    @exchange.exchange_messages.build(
      user_from: current_user.id,
      user_id: current_user.id,
      user_to: @item.user.id
    ) if current_user && @item.user

    respond_to do |format|
      format.html {render layout: false}
      format.js
    end
  end

  rescue_from NotOwner, :with => :not_activated

  def not_activated(exception)
    flash[:error] = "Você não é responsável por esse brinquedo."
    redirect_to root_path
  end

  def activate
    raise NotOwner unless current_user.id == @item.user_id
    
    @item.update_column(:next_notification_at, Date.today + 2.months)
    @item.update_column(:expired_at, nil)
    @item.update_column(:activate_qty, @item.activate_qty + 1)

    @item.credits.create(user_id: @item.user.id) if @item.credits.available.count == 0

    flash[:success] = "Seu brinquedo foi renovado por mais 2 meses."

    redirect_to my_items_path
  end


  # Dashboard

  def my_items
    @items = current_user.items.order("id DESC")
  end

  def new
    @item = current_user.items.new
    @item.zipcode = current_user.zipcode if !current_user.zipcode.blank?
    (1..4).each do |a|
      @item.item_images.build
    end
  end

  def edit
    @item.zipcode = current_user.zipcode if !current_user.zipcode.blank?
    (1..4-@item.item_images.count).each do |a|
      @item.item_images.build
    end
  end

  def create
    @item = current_user.items.new(item_params)

    respond_to do |format|
      if @item.save

        QuintalMailer.item_added(@item).deliver_now

        if params[:item_images]
          params[:item_images]['image'].each do |a|
            @item_image = @item.item_images.create!(:image => a)
          end
        end

        format.html { redirect_to my_items_path, notice: 'Brinquedo cadastrado com sucesso' }
        format.json { render :show, status: :created, location: @item }
      else
        format.html { render :new }
        format.json { render json: @item.errors, status: :unprocessable_entity }
      end
    end
  end

  def update

    respond_to do |format|
      if @item.update(item_params)

        format.html { redirect_to my_items_path, notice: 'O brinquedo foi atualizado com sucesso' }
        format.json { render :show, status: :ok, location: @item }
      else
        format.html { render :edit }
        format.json { render json: @item.errors, status: :unprocessable_entity }
      end
    end
  end

  def destroy
    @item.destroy
    respond_to do |format|
      format.html { redirect_to my_items_path, notice: 'O brinquedo foi removido com sucesso' }
      format.json { head :no_content }
    end
  end

  def frete
    ap "GO"
    frete = Correios::Frete::Calculador.new :cep_origem => "88110-690",
      :cep_destino => params[:cep],
      :peso => params[:weight],
      :comprimento => params[:length],
      :largura => params[:width],
      :altura => params[:height]

    ap "--------------------------------"
    ap frete
    servicos = frete.calcular :sedex, :pac
    ap "--------------------------------"
    ap servicos[:sedex].valor
    ap "--------------------------------"
    ap servicos[:pac].valor
    ap "--------------------------------"
    response = {}
    response[:sedex] = servicos[:sedex]
    response[:pac] = servicos[:pac]
    render json: response
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_item
      @item = Item.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def item_params
      params.require(:item).permit(:title, :price, :weight, :height, :width, :length, :description, :item_age_id, :item_category_id, :user_id, :tag_list, :zipcode, :latitude, :longitude, item_images_attributes: [:id, :item_id, :image, :featured])
    end
end
