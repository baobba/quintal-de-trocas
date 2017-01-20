class StoresController < InheritedResources::Base
  before_action :set_store, only: [:show, :edit, :update, :destroy]
  before_action :authenticate_user!

  def new
    @store = current_user.build_store
  end

  def edit
    a = Logger.new("#{Rails.root}/log/pagseguro_authorizations.log")
    a.info "------------------------------------------------------"

    credentials = PagSeguro::ApplicationCredentials.new(
      ENV["PAGSEGURO_APPID"],
      ENV["PAGSEGURO_APPKEY"]
    )
    a.info ENV["PAGSEGURO_APPID"]
    a.info credentials
    a.info "credentials"

    if params[:notificationCode]
      current_user.store.update_column(:pagseguro_notification_code, params[:notificationCode])
      @authorization = PagSeguro::Authorization.find_by_notification_code(current_user.store.pagseguro_notification_code, credentials: credentials )
    else
      options = {
        credentials: credentials,
        permissions: [:checkouts, :searches, :notifications],
        notification_url: 'https://quintaldetrocas.com.br/notify',
        redirect_url: edit_store_url(current_user.store.id),
        account: {
          email: current_user.email,
          type: 'SELLER'
        }
      }

      a.info "options"
      a.info options
      a.info "options"

      if current_user.store.pagseguro_notification_code
        @authorization = PagSeguro::Authorization.find_by_notification_code(current_user.store.pagseguro_notification_code, credentials: credentials )

        a.info "=> AUTHORIZATIONS"
        a.info @authorization
      end


      if !@authorization || (@authorization && @authorization.permissions.first.status == "DENIED")

        authorization_request = PagSeguro::AuthorizationRequest.new(options)
        a.info "authorization_request"
        a.info authorization_request

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

    respond_to do |format|
      if @store.save
        format.html { redirect_to edit_store_path(@store), success: 'Loja cadastrada com sucesso' }
        format.json { render :show, status: :created, location: @store }
      else
        format.html { render :new }
        format.json { render json: @store.errors, status: :unprocessable_entity }
      end
    end
  end

  def show
    @bs_container = false
    @items = @store.user.items
  end

  private

    def set_store
      @store = Store.find(params[:id])
    end

    def store_params
      params.require(:store).permit(:name, :user_id)
    end
end

