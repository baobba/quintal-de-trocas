class Credit < ActiveRecord::Base
  belongs_to :user
  belongs_to :exchange
  belongs_to :toy

  scope :available, -> { where(expired_at: nil) }

  def toy
    Toy.unscoped { super }
  end
end
