class ExchangesController < ApplicationController
  before_action :set_exchange, only: [:show, :edit, :update, :destroy, :reply_message]
  before_action :authenticate_user!, except: [:index]

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

    @user_from = User.find_by_id(exchange_params[:exchange_messages_attributes].first[1][:user_from])
    @user_to = User.find_by_id(exchange_params[:exchange_messages_attributes].first[1][:user_to])

    if @exchange.update(exchange_params)
      QuintalMailer.new_exchange_message(@exchange, @user_from, @user_to).deliver_now
      redirect_to exchange_path(@exchange), success: 'Mensagem enviada com sucesso'
    else
      render :show
    end
  end

  # Dashboard

  def my_exchanges

    @exchanges_total = Exchange.where(["item_from IN (?) OR item_to IN (?) OR user_id = ?", current_user.items.map(&:id), current_user.items.map(&:id), current_user.id])

    @exchanges = @exchanges_total
    @exchanges = @exchanges_total.where(user_id: current_user.id) if !params[:type].blank? && params[:type] == "sent"
    @exchanges = @exchanges_total.where(user_to: current_user.id) if !params[:type].blank? && params[:type] == "received"

    @exchanges = @exchanges.page params[:page]
  end

  def new
    @exchange = current_user.exchanges.new
  end

  def edit
  end

  def create
    @exchange = current_user.exchanges.new(exchange_params)
    @exchange.exchange_messages.last.user_from = current_user.id
    @exchange.exchange_messages.last.user_to = @exchange.item.user.id

    if @exchange.save
      QuintalMailer.request_exchange(@exchange).deliver_now
      redirect_to exchange_path(@exchange), success: 'Pedido de troca realizado com sucesso'
    else
      render :new
    end
  end

  def update

    @exchange.assign_attributes(exchange_params)

    if !@exchange.user_from_received.blank? || !@exchange.user_to_received.blank?
      QuintalMailer.item_arrived(@exchange, current_user).deliver_now
    end

    if !@exchange.user_from_received.blank? && !@exchange.user_to_received.blank?
      @exchange.finalized = true
      @exchange.finalized_at = Time.now

      # Se a troca for por credito, adiciona um credito para o usuario
      if @exchange.exchange_type == "credit"
        @exchange.to_user.credits.create(
          exchange_id: @exchange.id,
        ) if @exchange.credits.available.count == 0
      end
    end

    if @exchange.exchange_type_changed?

      QuintalMailer.exchange_changed(@exchange, @exchange.user, current_user).deliver_now
      @exchange.accepted = @exchange.exchange_type == "canceled" ? false : true
      if @exchange.exchange_type == "credit" && @exchange.user && @exchange.user.credits.available.count>0
        @exchange.user.credits.available.last.update_attributes(used_in_exchange_id: @exchange.id)
      end
      
    end

    respond_to do |format|

      if @exchange.save!
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
      params.require(:exchange).permit(:item_from, :item_to, :finalized, :finalized_at, :exchange_type, :exchange_deliver, :rating_from, :rating_to, :accepted, :user_id, :user_to, :user_to_received, :user_from_received, :reason, :credit_offer, :exchange_messages_attributes => [:id, :message, :user_to, :user_from, :exchange_id, :user_id])
    end
end
