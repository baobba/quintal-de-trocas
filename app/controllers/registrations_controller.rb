# class RegistrationsController < Devise::RegistrationsController
#   before_filter :configure_permitted_parameters, if: :devise_controller?

#   protected

#     def configure_permitted_parameters
#       puts "kkkkkkkk"
#       devise_parameter_sanitizer.for(:sign_up) { |u| u.permit(:email, :password, :password_confirmation, :name, :birthday, :gender, :phone, :username, :address) }
#       devise_parameter_sanitizer.for(:account_update) { |u| u.permit(:email, :password, :password_confirmation, :name, :birthday, :gender, :phone, :username, :address) }
#     end
# end

class RegistrationsController < Devise::RegistrationsController
  before_action :set_user, only: [:show, :edit, :update, :destroy]

  def update
    # @user = User.find(current_user.id)
    if @user.update(user_params)
      # redirect_to users_path, notice: 'O usuário "'+@user.nome+ '" foi atualizado com sucesso.'
      # set_flash_message :notice, :updated
      # Sign in the user bypassing validation in case his password changed
      # sign_in @user, :bypass => true
      redirect_to edit_user_registration_path, notice: 'Informações alteradas com sucesso.'
    else
      render "edit"
    end
  end

  protected

  def update_resource(resource, params)
    resource.update_without_password(params)
  end

  def set_user
    @user = User.find(current_user.id)
  end

  # Never trust parameters from the scary internet, only allow the white list through.
  def user_params
    params.require(:user).permit(:name, :birthday, :gender, :phone, :username, :street, :city, :state, :zipcode, :latitude, :longitude)
  end
end