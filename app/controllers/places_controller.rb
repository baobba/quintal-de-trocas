class PlacesController < ApplicationController
  before_action :set_place, only: [:show, :edit, :update, :destroy]
  before_action :authenticate_user!, except: [:index, :show]

  # GET /places
  # GET /places.json
  def index
    @q = Place.ransack(params[:q])
    @places = @q.result(distinct: true).order("id DESC").page params[:page]
    # @places = @places.where(state: params[:state]) unless params[:state].blank?

    respond_to do |format|
      format.html { }
      format.js { }
      format.json {
        render :json => Place.all.to_json
      }
    end
  end

  # GET /places/1
  # GET /places/1.json
  def show
  end

  # Dashboard

  def my_places
    @places = current_user.places
  end

  # GET /places/new
  def new
    @place = current_user.places.new
  end

  # GET /places/1/edit
  def edit
  end

  # POST /places
  # POST /places.json
  def create
    @place = current_user.places.new(place_params)

    respond_to do |format|
      if @place.save
        format.html { redirect_to my_places_path, success: 'Ponto de troca cadastrado com sucesso' }
        format.json { render :show, status: :created, location: @place }
      else
        format.html { render :new }
        format.json { render json: @place.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /places/1
  # PATCH/PUT /places/1.json
  def update
    respond_to do |format|
      if @place.update(place_params)
        format.html { redirect_to my_places_path, notice: 'Ponto de troca alterado com sucesso' }
        format.json { render :show, status: :ok, location: @place }
      else
        format.html { render :edit }
        format.json { render json: @place.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /places/1
  # DELETE /places/1.json
  def destroy
    @place.destroy
    respond_to do |format|
      format.html { redirect_to places_url, notice: 'Place was successfully destroyed.' }
      format.json { head :no_content }
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_place
      @place = Place.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def place_params
      params.require(:place).permit(:title, :office_hours, :phone, :phone_alt, :description, :zipcode, :street, :city, :state, :latitude, :longitude)
    end
end
