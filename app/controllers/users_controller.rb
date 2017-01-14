# encoding: utf-8
class UsersController < ApplicationController
  before_action :set_user, only: [:show, :edit, :update, :destroy]
  before_filter :authenticate_user!, except: [:show]
  # before_filter :authenticate_user!, :verify_is_admin, except: [:index, :edit]

  def index
    @users = if current_user.atendente
      current_user.with_attendants.order("id desc")
    elsif current_user.is_admin?
      User.order("id desc")
    end

    @users = @users.where("nome ilike '%#{params[:nome]}%'") if !params[:nome].blank?
    @users = @users.where(["atendente = ?", params[:atendente]]) if !params[:atendente].blank?
    
    if !params[:tipo].blank?
      if params[:tipo] == "0"
        @users = @users.where("access IS NULL")
      else
        @users = @users.where(["access = ?", params[:tipo]])
      end
    end

    @users = @users.paginate(:page => params[:page])
  end

  def show
    @items = @user.items
  end

  def edit
  end

  def update
    respond_to do |format|
      if current_user.is_admin?
        @user.attributes = user_params

        @user.reset_password_token = "temp_#{@user.id}"
        if @user.reset_password(params[:user][:password], params[:user][:password_confirmation])
          # Quital.admin_password_change(@user, params[:user][:password]).deliver
        end

        if @user.save(:validate => false)
          format.html { redirect_to users_path, notice: 'O usuário foi atualizado com sucesso.' }
        end
      else

        if @user.update(user_params)
          format.html { redirect_to users_path, notice: 'O usuário foi atualizado com sucesso.' }
        else
          format.html { render action: 'edit' }
        end
      end
      
    end
  end

  # def destroy
  #   resource.destroy
  #   Devise.sign_out_all_scopes ? sign_out : sign_out(resource_name)
  #   set_flash_message :notice, :destroyed if is_navigational_format?
  #   respond_with_navigational(resource){ redirect_to after_sign_out_path_for(resource_name) }
  # end

  def destroy
    if @user.destroy
      redirect_to users_url, notice: "Usuário removido com sucesso"
    end
  end


  private
    # Use callbacks to share common setup or constraints between actions.
    def set_user
      @user = User.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def user_params
      params.require(:user).permit(:user_id, :name, :cpf, :empresa, :sexo, :data_nascimento, :tel_comercial, :tel_celular, :escolaridade, :profissao, :site, :news_sandvik, :msg_sms, :atendente, :atendente_tipo, :email, :password, :password_confirmation, :current_password, :access, :region, :city)
    end
end