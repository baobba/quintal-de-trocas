class Exchange < ActiveRecord::Base

  acts_as_taggable
  
  belongs_to :toy, :foreign_key => "toy_from"

  default_scope { order("created_at DESC") }
  
end

