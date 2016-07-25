class ToyImage < ActiveRecord::Base

  mount_uploader :image, ToyUploader
  belongs_to :toy

  scope :featured, -> { where(featured: true) }
  
end