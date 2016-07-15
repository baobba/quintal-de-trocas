class ExchangesController < ApplicationController
  before_action :set_exchange, only: [:show, :edit, :update, :destroy]
  before_action :authenticate_user!, except: [:index, :show]

  def index
    @exchanges = Exchange.page params[:page]
  end

  def show
  	@messages = @exchange.mailbox.conversations
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

    @exchange.send_message(@exchange.user, @exchange.message, "Bora trocar brinquedo?")

    if @exchange.save
      redirect_to exchange_path(@exchange), success: 'Pedido de troca realizado com sucesso'
    else
      render :new
    end
  end

  def update
    respond_to do |format|
      if @exchange.update(exchange_params)
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

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_exchange
      @exchange = Exchange.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def exchange_params
      params.require(:exchange).permit(:toy_from, :toy_to, :exchange_time, :exchange_date, :exchange_date, :message, :status, :user_id)
    end
end
