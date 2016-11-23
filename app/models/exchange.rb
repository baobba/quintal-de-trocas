class Exchange < ActiveRecord::Base

  acts_as_taggable
  acts_as_paranoid

  belongs_to :toy, -> { with_deleted }, :foreign_key => "toy_to"
  belongs_to :user, -> { with_deleted }
  has_many :exchange_messages, dependent: :destroy
  has_many :credits
  accepts_nested_attributes_for :exchange_messages, 
    allow_destroy: true, 
    reject_if: :all_blank

  scope :waiting, -> { where(accepted: nil) }
  default_scope { order("created_at DESC") }

  after_create :send_slack_message

  acts_as_messageable

  def from_toy
    Toy.with_deleted.find_by_id(toy_from)
  end

  def to_toy
    toy
  end

  def from_user
    self.user
  end

  def to_user
    Toy.with_deleted.find_by_id(toy_to).user
  end

  def toy
    Toy.unscoped { super }
  end

  def send_slack_message
    message = "#{from_user.name} (#{from_user.email}), acabou de se solicitar uma troca com #{to_user.name} (#{to_user.email})."
    NOTIFIER.ping(message, icon_url: ApplicationController.helpers.default_img(from_user))
  end

end

