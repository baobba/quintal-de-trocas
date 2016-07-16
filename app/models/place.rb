class Place < ActiveRecord::Base

  belongs_to :user

  validates :title, presence: true

  geocoded_by :zipcode
  after_validation :geocode

  def hey
    [zipcode, "Brasil"].compact.join(', ')
  end

  def to_param
    [id, title.parameterize].join("-")
  end
end
