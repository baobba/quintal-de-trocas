class ApplicationController < ActionController::Base
  # Prevent CSRF attacks by raising an exception.
  # For APIs, you may want to use :null_session instead.
  protect_from_forgery with: :exception

  require 'carrierwave/orm/activerecord'


  before_filter :configure_permitted_parameters, if: :devise_controller?


  

  protected

    def configure_permitted_parameters
      devise_parameter_sanitizer.permit(:sign_up, keys: [:email, :password, :password_confirmation, :name, :birthday, :gender, :phone, :username, :street, :city, :state, :zipcode, :latitude, :longitude])
      devise_parameter_sanitizer.permit(:account_update, keys: [:email, :password, :password_confirmation, :current_password, :name, :birthday, :gender, :phone, :username, :street, :city, :state, :zipcode, :latitude, :longitude])
    end

end
