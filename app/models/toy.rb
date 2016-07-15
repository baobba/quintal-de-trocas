class Toy < ActiveRecord::Base

  acts_as_taggable

  belongs_to :toy_category
  belongs_to :toy_age
  belongs_to :user
  has_many :toy_images

  has_many :exchanges, :foreign_key => "toy_to"

  accepts_nested_attributes_for :toy_images, :allow_destroy => true, :reject_if => proc { |attributes| attributes['image'].blank? }

  validates :title, :description, :toy_category, :toy_age, presence: true

  paginates_per 12

  geocoded_by :zipcode


  before_validation :add_zipcode

  def add_zipcode
    self.zipcode = user.zipcode
    self.latitude = user.latitude
    self.longitude = user.longitude
  end

  def fet_image
    if toy_images.count > 0
      toy_images.featured.first
    else
      toy_images.new
    end
  end

  def to_param
    [id, title.parameterize].join("-")
  end

end
