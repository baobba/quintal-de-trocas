class ToysController < ApplicationController
  before_action :set_toy, only: [:show, :edit, :update, :destroy, :exchange, :activate]
  before_action :authenticate_user!, except: [:index, :show, :index_near, :exchange, :activate]

  class NotOwner < StandardError
  end

  def index
    @bs_container = false

    @q = Toy.includes(:toy_category, :toy_age, :toy_images)

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
          params[:q].blank? ? 50 : params[:within] || 50
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

    @q = @q.near(location, zoom, :units => :km, :order => 'distance') if params[:tipo].blank?
    @q = @q.search(params[:q])

    respond_to do |format|

      format.all {
        @toys = @q.result(distinct: true).order("distance").page params[:page]
      }

      format.js {
        @toys = @q.result(distinct: true).order("distance").page params[:page]
      }

      format.json {

        @toys = @q.result(distinct: true).order("created_at DESC")
        @toys = @toys.page params[:page] if !params[:tipo].blank?

        @toys = @toys.map{|f| [f.id, f.title, f.latitude, f.longitude, "<div class='info_content'><p class='lead' style='margin: 5px 0 10px 0;font-size: 16px;line-height: 120%;'><a href='#{toy_url(f)}'>#{f.title}</a></p><div style='margin-left:15px;' class='pull-right'><img src='#{f.toy_images.first.image.url(:thumb) if f.toy_images.first}' class='img-thumbnail' width='80'></div><small class='text-muted'>#{f.description[0..100]} ...</small></div>"]}
        render json: @toys
      }
    end

  end

  def show
    @toy_images = @toy.toy_images.all

    respond_to do |format|
      format.html { }
      format.json { render json: @toy }
    end
  end


  def exchange
    @exchange = @toy.exchanges.new(toy_to: @toy.user.id)

    @exchange.exchange_messages.build(
      user_from: current_user.id,
      user_id: current_user.id,
      user_to: @toy.user.id
    ) if current_user && @toy.user

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
    raise NotOwner unless current_user.id == @toy.user_id
    
    @toy.update_column(:next_notification_at, Date.today + 2.months)
    @toy.update_column(:expired_at, nil)

    @toy.credits.create(user_id: @toy.user.id) if @toy.credits.available.count == 0

    flash[:success] = "Seu brinquedo foi renovado por mais 2 meses."

    redirect_to my_toys_path
  end


  # Dashboard

  def my_toys
    @toys = current_user.toys.order("id DESC")
  end

  def new
    @toy = current_user.toys.new
    @toy.zipcode = current_user.zipcode if !current_user.zipcode.blank?
    (1..4).each do |a|
      @toy.toy_images.build
    end
  end

  def edit
    @toy.zipcode = current_user.zipcode if !current_user.zipcode.blank?
    (1..4-@toy.toy_images.count).each do |a|
      @toy.toy_images.build
    end
  end

  def create
    @toy = current_user.toys.new(toy_params)

    respond_to do |format|
      if @toy.save

        QuintalMailer.toy_added(@toy).deliver_now

        if params[:toy_images]
          params[:toy_images]['image'].each do |a|
            @toy_image = @toy.toy_images.create!(:image => a)
          end
        end

        format.html { redirect_to my_toys_path, notice: 'Brinquedo cadastrado com sucesso' }
        format.json { render :show, status: :created, location: @toy }
      else
        format.html { render :new }
        format.json { render json: @toy.errors, status: :unprocessable_entity }
      end
    end
  end

  def update

    respond_to do |format|
      if @toy.update(toy_params)

        format.html { redirect_to my_toys_path, notice: 'O brinquedo foi atualizado com sucesso' }
        format.json { render :show, status: :ok, location: @toy }
      else
        format.html { render :edit }
        format.json { render json: @toy.errors, status: :unprocessable_entity }
      end
    end
  end

  def destroy
    @toy.destroy
    respond_to do |format|
      format.html { redirect_to my_toys_path, notice: 'O brinquedo foi removido com sucesso' }
      format.json { head :no_content }
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_toy
      @toy = Toy.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def toy_params
      params.require(:toy).permit(:title, :description, :toy_age_id, :toy_category_id, :user_id, :tag_list, :zipcode, :latitude, :longitude, toy_images_attributes: [:id, :toy_id, :image, :featured])
    end
end
