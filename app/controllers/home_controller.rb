class HomeController < ActionController::Base
  layout 'application'
  def index
    @toys = Toy.all

    @toys = @toys.where(toy_category_id: params[:category]) unless params[:category].blank?
    @toys = @toys.where(toy_age_id: params[:age]) unless params[:age].blank?
  end
end