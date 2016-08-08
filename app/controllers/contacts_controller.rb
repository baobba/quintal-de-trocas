class ContactsController < ApplicationController

  def index
    render :file => "pages/contact_us"
  end

  def new
    @message = Contact.new
    render :file => "pages/contact_us"
  end

  def create
    @message = Contact.new(contact_params)

    if @message.valid?
      QuintalMailer.contact_us(@message).deliver_now
      redirect_to contact_us_path, notice: "Sua mensagem foi enviada com sucesso"
    else
      flash[:error] = "Por favor, preencha os campos necessários."
      render :file => "pages/contact_us"
    end
  end

private

  def contact_params
    params.require(:contact).permit(:name, :email, :city, :state, :subject, :message)
  end

end
