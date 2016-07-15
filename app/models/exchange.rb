class Exchange < ActiveRecord::Base

  acts_as_taggable

  belongs_to :toy, :foreign_key => "toy_to"
  belongs_to :user

  default_scope { order("created_at DESC") }

  acts_as_messageable

end

