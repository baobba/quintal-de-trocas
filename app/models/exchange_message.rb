class ExchangeMessage < ActiveRecord::Base
  belongs_to :user, :foreign_key => "user_from"
  belongs_to :exchange

  after_create :send_slack_message

  def from_user
    self.user
  end

  def to_user
    User.with_deleted.find_by_id(user_to)
  end

  def send_slack_message
    message = "#{from_user.name} (#{from_user.email}), acabou de enviar uma mensagem para #{to_user.name} (#{to_user.email})."
    NOTIFIER.ping(message, icon_emoji: ApplicationController.helpers.default_img(from_user))
  end
end
