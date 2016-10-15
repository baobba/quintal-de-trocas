class OrdersController < ApplicationController
  before_action :set_order, only: [:show, :edit, :update, :destroy]
  before_action :authenticate_user!, only: [:my_orders, :show]


  # Dashboard
  
  # GET /orders
  # GET /orders.json
  def my_orders
    @orders = current_user.orders.order("id DESC").all

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @orders }
    end
  end

  # GET /orders/1
  # GET /orders/1.json
  def show
    @order = Order.find(params[:id])
    @response = PagSeguro::Transaction.find_by_code(@order.code)

    respond_to do |format|
      format.html # show.html.erb
      format.json { render json: @order }
    end
  end

  # -----------------------------------------

  def notify
    headers['Access-Control-Allow-Origin'] = 'https://sandbox.pagseguro.uol.com.br'
    headers['Access-Control-Allow-Origin'] = 'https://pagseguro.uol.com.br'
    headers['Access-Control-Allow-Origin'] = '177.97.184.66'

    if request.post?

      transaction = PagSeguro::Transaction.find_by_notification_code(params[:notificationCode])
      @order = Order.find_by_code(transaction.code)
      
      if @order
        @order.status = transaction.status.id

        tipo = @order.title.split(" ").first.downcase
        if tipo == "produto" && (transaction.status.id == "3" || transaction.status.id == "4")
          ap "paga"
        else
          ap "nao foi paga"
        end

        if @order.save
          ap "salvou"
        else
          ap "n salvou"
        end
      end

    else
    end

    render text: "oi"
  end

  

  # GET /orders/new
  # GET /orders/new.json
  def new
    @order = current_user.orders.new
    @order.toy_id =  params[:product]
    @toy = @order.toy

    # 1. Get Pagseguro valid session
    session = PagSeguro::Session.create
    @session_id = session.id
    ap @session_id

    respond_to do |format|
      format.html # new.html.erb
      format.json { render json: @order }
    end
  end

  # GET /orders/1/edit
  def edit
    @order = Order.find(params[:id])
  end

  # POST /orders
  # POST /orders.json
  def create
    @order = Order.new(params[:order])
    @toy = @order.toy

    # Fields to add to user model
    # 1.1 price
    # 1.2 weight
    # 1.3 size
    # 1.4 quantity

    # 2. Browser integration (load JS) - OK
    # 3. Buyer identification (JS) - ERB Page
    #    PagSeguroDirectPayment.getSenderHash(); // after form submit
    # 4. Get brand by card number
    # 5. Get credit card token
    # 6. Get credit card installments (parcelamentos)

    notification_url = "http://bff76df8.ngrok.io/notify"

    if params[:paymentMethod] == "credit_card"

      payment = PagSeguro::CreditCardTransactionRequest.new
      payment.notification_url = notification_url
      payment.payment_mode = "gateway"
      payment.reference = "REF#{@order.id}-#{@toy.id}-credit-card"

    elsif params[:paymentMethod] == "boleto"

      payment = PagSeguro::BoletoTransactionRequest.new
      payment.notification_url = notification_url
      payment.payment_mode = "default"
      payment.reference = "REF#{@order.id}-#{@toy.id}-boleto"
    end

    # Set items
    payment.items << {
      id: @order.id,
      description: @toy.title,
      amount: (@toy.price || 50.00),
      weight: 1
    }

    # Set sender
    payment.sender = {
      hash: params[:sender_hash],
      name: current_user.name,
      email: "c64135579735859220234@sandbox.pagseguro.com.br",
      #email: current_user.email,
      document: { type: "CPF", value: params[:cpf].gsub(/\D/, '') },
      phone: {
        area_code: 48,
        number: "99355794"
      }
    }

    # Set shipping
    payment.shipping = {
      type_name: "sedex",
      address: {
        street: @order.street,
        number: 10,
        complement: @order.complement,
        city: @order.city,
        state: @order.state,
        district: @order.neighborhood,
        postal_code: @order.zipcode
      }
    }

    payment.billing_address = {
      street: @order.street,
      number: 10,
      complement: @order.complement,
      city: @order.city,
      state: @order.state,
      district: @order.neighborhood,
      postal_code: @order.zipcode
    }

    if params[:paymentMethod] == "credit_card"

      payment.credit_card_token = params[:token]
      payment.holder = {
        name: params[:creditcard_name],
        birth_date: "07/05/1981",
        document: {
          type: "CPF",
          value: params[:cpf].gsub(/\D/, '')
        },
        phone: {
          area_code: 48,
          number: "99355794"
        }
      }

      payment.installment = {
        value: 55.50,
        quantity: 1
      }
    end

    puts "=> REQUEST"
    puts PagSeguro::TransactionRequest::RequestSerializer.new(payment).to_params
    puts

    payment.create


    a = Logger.new("#{Rails.root}/log/orders.log")

    a.info "------------------------------------------------------------------------------------------------"
    a.info Time.now


    if payment.errors.any?
      a.info "=> ERRORS"
      a.info payment.errors.join("\n")
    else

      a.info "=> Transaction"
      a.info "  code: #{payment.code}"
      a.info "  reference: #{payment.reference}"
      a.info "  type: #{payment.type_id}"
      a.info "  payment link: #{payment.payment_link}"
      a.info "  status: #{payment.status}"
      a.info "  payment method type: #{payment.payment_method}"
      a.info "  created at: #{payment.created_at}"
      a.info "  updated at: #{payment.updated_at}"
      a.info "  gross amount: #{payment.gross_amount.to_f}"
      a.info "  discount amount: #{payment.discount_amount.to_f}"
      a.info "  net amount: #{payment.net_amount.to_f}"
      a.info "  extra amount: #{payment.extra_amount.to_f}"
      a.info "  installment count: #{payment.installment_count}"

      a.info "    => Items"
      a.info "      items count: #{payment.items.size}"
      payment.items.each do |item|
        a.info "      item id: #{item.id}"
        a.info "      description: #{item.description}"
        a.info "      quantity: #{item.quantity}"
        a.info "      amount: #{item.amount.to_f}"
      end

      a.info "    => Sender"
      a.info "      name: #{payment.sender.name}"
      a.info "      email: #{payment.sender.email}"
      a.info "      phone: (#{payment.sender.phone.area_code}) #{payment.sender.phone.number}"
      a.info "      document: #{payment.sender.document}: #{payment.sender.document}"

      a.info "    => Shipping"
      a.info "      street: #{payment.shipping.address.street}, #{payment.shipping.address.number}"
      a.info "      complement: #{payment.shipping.address.complement}"
      a.info "      postal code: #{payment.shipping.address.postal_code}"
      a.info "      district: #{payment.shipping.address.district}"
      a.info "      city: #{payment.shipping.address.city}"
      a.info "      state: #{payment.shipping.address.state}"
      a.info "      country: #{payment.shipping.address.country}"
      a.info "      type: #{payment.shipping.type_name}"
      a.info "      cost: #{payment.shipping.cost}"
      
      @order = current_user.orders.new({
        code: payment.code,
        title: payment.items.first.description,
        price: payment.gross_amount.to_f,
        status: payment.status
      })

      if @order.save
        redirect_to :back, notice: "Assim que o pagamento for aprovado, você receberá um e-mail com detalhes sobre entrega do brinquedo."
      end
    end

    # respond_to do |format|
    #   if @order.save
    #     format.html { redirect_to @order, notice: 'Order was successfully created.' }
    #     format.json { render json: @order, status: :created, location: @order }
    #   else
    #     format.html { render action: "new" }
    #     format.json { render json: @order.errors, status: :unprocessable_entity }
    #   end
    # end
  end

  # PUT /orders/1
  # PUT /orders/1.json
  def update
    @order = Order.find(params[:id])

    respond_to do |format|
      if @order.update_attributes(params[:order])
        format.html { redirect_to @order, notice: 'Order was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @order.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /orders/1
  # DELETE /orders/1.json
  def destroy
    @order = Order.find(params[:id])
    @order.destroy

    respond_to do |format|
      format.html { redirect_to orders_url }
      format.json { head :no_content }
    end
  end


  private
    # Use callbacks to share common setup or constraints between actions.
    def set_order
      @order = Order.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def order_params
      params.require(:order).permit(:code, :title, :price, :status, :user_id)
    end
end
