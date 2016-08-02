class SessionsController < Devise::SessionsController
  def create
    resource = warden.authenticate!(:scope => resource_name, :recall => 'sessions#failure')
    sign_in_and_redirect(resource_name, resource)
  end

  def sign_in_and_redirect(resource_or_scope, resource=nil)
    scope = Devise::Mapping.find_scope!(resource_or_scope)
    resource ||= resource_or_scope
    sign_in(scope, resource) unless warden.user(scope) == resource

    respond_to do |format|
      format.html { redirect_to root_path, notice: "Login efetuado com sucesso!" }
      format.js { render :json => { :success => true } }
    end
  end

  def failure
    respond_to do |format|
      format.html {
        redirect_to :back, notice: "E-mail ou senha inválidos."
      }
      format.js { render :json => { :success => false, :errors => ["E-mail ou senha inválidos."] } }
    end

  end
end