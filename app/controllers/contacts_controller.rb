class ContactsController < ApplicationController

  def index
    render: :file => "pages/contact_us"
  end

  def new
    @message = Contact.new
  end

  def create
    @message = Contact.new(message_params)

    if @message.valid?
      # MessageMailer.new_message(@message).deliver
      redirect_to contact_path, notice: "Your messages has been sent."
    else
      flash[:alert] = "An error occurred while delivering this message."
      render :new
    end
  end

private

  def message_params
    params.require(:message).permit(:name, :email, :content)
  end

end
