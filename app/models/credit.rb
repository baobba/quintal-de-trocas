class Credit < ActiveRecord::Base
  belongs_to :user
  belongs_to :exchange
  belongs_to :used_in_exchange, :class_name => 'Exchange', :foreign_key => "used_in_exchange_id"
  belongs_to :toy

  scope :available, -> { where("expired_at IS NULL AND used_in_exchange_id IS NULL") }

  def toy
    Toy.unscoped { super }
  end

  def available?
    expired_at.blank? && used_in_exchange_id.blank?
  end
end
