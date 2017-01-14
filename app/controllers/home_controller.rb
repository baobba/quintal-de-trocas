class HomeController < ActionController::Base
  layout 'application'
  protect_from_forgery with: :null_session, if: Proc.new { |c| c.request.format.json? }

  def index
    @bs_container = false
    
    @items = Item.all

    @items = @items.where(item_category_id: params[:category]) unless params[:category].blank?
    @items = @items.where(item_age_id: params[:age]) unless params[:age].blank?
  end

  def get_mailgun_data
    a = Logger.new("#{Rails.root}/log/click.log")

    a.info "---------------------------------------------------------------------------------"
    a.info Time.now
    a.info params

    @mailgun = params

    if @mailgun
      event = @mailgun['event']
      email = @mailgun['recipient']
      subject = @mailgun['subject']
      tags = @mailgun['tag']

      a.info tags
      a.info tags
      a.info event
      a.info email
      a.info subject

      user = User.find_by_email(email)
      if user

        if event == "opened" && subject == "Confirme seu cadastro"
          # user.update_column(:confirmed, true) if user && user.confirmed == false
          a.info "Confirm the user"
        end

        if event == "failed" && subject == "Confirme seu cadastro"
          # user.update_column(:confirmed, false) if user && user.confirmed == true
          a.info "Remove the user"
        end

        # ------------------------

        if event == "complained" || event == "unsubscribed" || event == "failed" || event == "rejected"
          a.info "Remove user from newsletter"
        end

        # ------------------------

        if event == "opened" && tags && tags.include?("mensagem negocio")
          # mensagem = ExchangeMessage.find(@mailgun['id'])
          # mensagem.update_attributes(:read_at => Time.now)
          a.info "Set message as read"
        end

        # ------------------------

        if event == "failed"
          a.info "Hard bounce??"
          a.info user
        end

        a.info "END"

      end
    end
    a.info "------------------------------------------------------------------------------------"

    render :text => ":)"
  end
end