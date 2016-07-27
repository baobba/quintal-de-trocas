class ToysController < ApplicationController
  before_action :set_toy, only: [:show, :edit, :update, :destroy]
  before_action :authenticate_user!, except: [:index, :show, :index_near]

  def index
    @bs_container = false
    
    @q = Toy.includes(:toy_category, :toy_age).ransack(params[:q])
    @toys = @q.result(distinct: true).order("id DESC").page params[:page]
  end

  def show
    @toy_images = @toy.toy_images.all
    @exchange = @toy.exchanges.new

    @exchange.exchange_messages.build(
      user_from: current_user.id,
      user_id: current_user.id,
      user_to: @toy.user.id
    ) if current_user && @toy.user

    respond_to do |format|
      format.html { }
      format.json { render json: @toy }
    end
  end

  def index_near
    city = request.location.city
    @toys = Toy.near('88110-690, Brasil', 200, :units => :km) || nil
    @toys = @toys.map{|f| [f.title, f.latitude, f.longitude, "<div class='info_content'><p class='lead' style='margin: 5px 0 10px 0;font-size: 16px;line-height: 120%;'><a href='#{toy_url(f)}'>#{f.title}</a></p><div style='margin-left:15px;' class='pull-right'><img src='#{f.toy_images.first.image.url(:thumb) if f.toy_images.first}' class='img-thumbnail' width='80'></div><small class='text-muted'>#{f.description[0..100]} ...</small></div>"]} if @toys
    render :json => @toys
  end


  # Dashboard

  def my_toys
    @toys = current_user.toys
  end

  def new
    @toy = current_user.toys.new
    (1..5).each do |a|
      @toy.toy_images.build
    end
  end

  def edit
    @toy_image = @toy.toy_images.build
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
      format.html { redirect_to toys_url, notice: 'O brinquedo foi removido com sucesso' }
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
