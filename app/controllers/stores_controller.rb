class StoresController < InheritedResources::Base
  before_action :set_store, only: [:show, :edit, :update, :destroy]
  before_action :authenticate_user!

  def new
    @store = current_user.build_store
  end

  def edit
    credentials = PagSeguro::ApplicationCredentials.new(
      Rails.application.secrets['pagseguro_appid'],
      Rails.application.secrets['pagseguro_appkey']
    )
    ap credentials

    if params[:notificationCode]
      current_user.store.update_column(:pagseguro_notification_code, params[:notificationCode])
      @authorization = PagSeguro::Authorization.find_by_notification_code(current_user.store.pagseguro_notification_code, credentials: credentials )
    else
      options = {
        credentials: credentials,
        permissions: [:checkouts],
        notification_url: 'http://2e7a2efd.ngrok.io/notify',
        redirect_url: edit_store_url(current_user.store.id),
        account: {
          email: current_user.email,
          type: 'SELLER'
        }
      }
      ap options

      if current_user.store.pagseguro_notification_code
        @authorization = PagSeguro::Authorization.find_by_notification_code(current_user.store.pagseguro_notification_code, credentials: credentials )
      end

      ap @authorization

      if !@authorization || (@authorization && @authorization.permissions.first.status == "DENIED")

        authorization_request = PagSeguro::AuthorizationRequest.new(options)
        ap authorization_request

        if authorization_request.create
          print "Use this link to confirm authorizations: "
          puts authorization_request.url

          @authorization_request_link ||= authorization_request.url

        else
          puts authorization_request.errors.join("\n")
        end
      end
    end

    
  end

  def create
    # @store = current_user.build_store(store_params)
    @store = current_user.create_store(store_params)
    ap @store

    respond_to do |format|
      if @store.save
        ap @store
        format.html { redirect_to root_path, success: 'Loja cadastrada com sucesso' }
        format.json { render :show, status: :created, location: @store }
      else
        format.html { render :new }
        format.json { render json: @store.errors, status: :unprocessable_entity }
      end
    end
  end

  private

    def set_store
      @store = Store.find(params[:id])
    end

    def store_params
      params.require(:store).permit(:name, :user_id)
    end
end

