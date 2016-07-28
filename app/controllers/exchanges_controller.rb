class ExchangesController < ApplicationController
  before_action :set_exchange, only: [:show, :edit, :update, :destroy, :reply_message]
  before_action :authenticate_user!, except: [:index, :show]

  def index
    @exchanges = Exchange.page params[:page]
  end

  def show
  	@messages = @exchange.exchange_messages.where("created_at IS NOT NULL")
    @exchange_message = @exchange
  end

  def reply
    @exchange = Exchange.find(params[:exchange_id])

    respond_to do |format|
      format.html
      format.js
    end
  end

  def reply_message

    if @exchange.update(exchange_params)
      QuintalMailer.new_exchange_message(@exchange, @exchange.toy.user).deliver_now
      redirect_to exchange_path(@exchange), success: 'Mensagem enviada com sucesso'
    else
      render :show
    end
  end

  # Dashboard

  def my_exchanges
    @exchanges = Exchange.where(["toy_from IN (?) OR toy_to IN (?) OR user_id = ?", current_user.toys.map(&:id), current_user.toys.map(&:id), current_user.id])
  end

  def new
    @exchange = current_user.exchanges.new
  end

  def edit
  end

  def create
    @exchange = current_user.exchanges.new(exchange_params)
    @exchange.exchange_messages.last.user_from = current_user.id
    @exchange.exchange_messages.last.user_to = @exchange.toy.user.id

    # @exchange.send_message(@exchange.user, @exchange.message, "Bora trocar brinquedo?")

    if @exchange.save
      QuintalMailer.request_exchange(@exchange, @exchange.toy.user).deliver_now
      redirect_to exchange_path(@exchange), success: 'Pedido de troca realizado com sucesso'
    else
      render :new
    end
  end

  def update

    @exchange.accepted = @exchange.reason.blank? ? true : false

    if !exchange_params[:finalized].nil? && exchange_params[:finalized] == 'true'
      @exchange.to_user.credits.create(
        is_available: true,
        exchange_id: @exchange.id,
      ) if !@exchange.credit
    elsif !exchange_params[:finalized].nil?
      @exchange.credit.destroy!
    end

    respond_to do |format|
      if @exchange.update(exchange_params)
        # QuintalMailer.exchange_changed(@exchange, @exchange.toy.user).deliver_now if @exchange.status_changed?
        format.html { redirect_to @exchange, notice: 'Troca atualizada com sucesso' }
        format.json { render :show, status: :ok, location: @exchange }
      else
        format.html { render :edit }
        format.json { render json: @exchange.errors, status: :unprocessable_entity }
      end
    end
  end

  def destroy
    @exchange.destroy
    respond_to do |format|
      format.html { redirect_to exchanges_url, notice: 'Exchange was successfully destroyed.' }
      format.json { head :no_content }
    end
  end

  def toggle_status
    @exchange = Exchange.find(params[:exchange_id])
    status = params[:status]

    if @exchange.update_column(:status, status)
      redirect_to exchange_path(@exchange), success: 'heyy'
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_exchange
      @exchange = Exchange.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def exchange_params
      params.require(:exchange).permit(:toy_from, :toy_to, :finalized, :finalized_at, :exchange_type, :exchange_deliver, :rating_from, :rating_to, :accepted, :user_id, :reason, :exchange_messages_attributes => [:id, :message, :user_to, :user_from, :exchange_id, :user_id])
    end
end
