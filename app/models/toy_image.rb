class ToyImage < ActiveRecord::Base

  mount_base64_uploader :image, ToyUploader
  belongs_to :toy

  scope :featured, -> { where(featured: true) }
  
end