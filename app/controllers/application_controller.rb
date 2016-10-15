class ApplicationController < ActionController::Base
  # Prevent CSRF attacks by raising an exception.
  # For APIs, you may want to use :null_session instead.
  protect_from_forgery with: :exception
  protect_from_forgery with: :null_session,
      if: Proc.new { |c| c.request.format =~ %r{application/json} }

  require 'carrierwave/orm/activerecord'


  before_filter :configure_permitted_parameters, if: :devise_controller?

  helper_method :lookup_ip_location
  
  def lookup_ip_location
    if Rails.env.development?
      Geocoder.search(request.remote_ip).first
    else
      request.location
    end
  end

  def access_denied(exception)
    redirect_to root_path, alert: exception.message
  end
  

  protected

    def configure_permitted_parameters
      devise_parameter_sanitizer.permit(:sign_up, keys: [:email, :password, :password_confirmation, :name, :birthday, :gender, :phone, :username, :street, :complement, :neighborhood, :city, :state, :zipcode, :latitude, :longitude])
      devise_parameter_sanitizer.permit(:account_update, keys: [:email, :password, :password_confirmation, :current_password, :name, :birthday, :gender, :phone, :username, :street, :complement, :neighborhood, :city, :state, :zipcode, :latitude, :longitude])
    end

end
