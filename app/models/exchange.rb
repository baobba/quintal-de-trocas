class Exchange < ActiveRecord::Base

  acts_as_taggable

  belongs_to :toy, -> { with_deleted }, :foreign_key => "toy_to"
  belongs_to :user, -> { with_deleted }
  has_many :exchange_messages
  has_one :credit
  accepts_nested_attributes_for :exchange_messages, 
    allow_destroy: true, 
    reject_if: :all_blank

  scope :waiting, -> { where(accepted: nil) }
  default_scope { order("created_at DESC") }

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

end

