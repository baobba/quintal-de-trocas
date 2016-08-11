class Credit < ActiveRecord::Base
  belongs_to :user
  belongs_to :exchange

  scope :available, -> { where(expired_at: nil) }
end
