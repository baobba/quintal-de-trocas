class Exchange < ActiveRecord::Base

  acts_as_taggable

  belongs_to :toy, :foreign_key => "toy_to"
  belongs_to :user
  has_many :exchange_messages
  accepts_nested_attributes_for :exchange_messages, 
    allow_destroy: true, 
    reject_if: :all_blank

  default_scope { order("created_at DESC") }

  acts_as_messageable

end

