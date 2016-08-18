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

  def edit
    (1..(@user.user_children.count > 0 ? 1 : 2)).each do
      @user.user_children.build
    end
  end

  def update
    # @user = User.find(current_user.id)
    
    @user.assign_attributes(user_params)

    if @user.save(validate: false)
      # redirect_to users_path, notice: 'O usuário "'+@user.nome+ '" foi atualizado com sucesso.'
      # set_flash_message :notice, :updated
      # Sign in the user bypassing validation in case his password changed
      # sign_in @user, :bypass => true
      redirect_to edit_user_registration_path, notice: 'Informações alteradas com sucesso.'
    else
      render "edit"
    end
  end

  def login_modal
  end

  def sign_in_and_redirect(resource_or_scope, resource=nil)
    scope = Devise::Mapping.find_scope!(resource_or_scope)
    resource ||= resource_or_scope
    sign_in(scope, resource) unless warden.user(scope) == resource
    return render :json => {:success => true}
  end

  def failure
    return render :json => {:success => false, :errors => ["Login failed."]}
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
    params.require(:user).permit(:name, :avatar, :birthday, :gender, :phone, :username, :street, :complement, :neighborhood, :city, :state, :zipcode, :latitude, :longitude, user_children_attributes: [:id, :name, :birthday, :gender])
  end
end