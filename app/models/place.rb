class Place < ActiveRecord::Base

  belongs_to :user
  
  validates :title, presence: true
  
  geocoded_by :zipcode
  after_validation :geocode
  
  paginates_per 5

  def hey
    [zipcode, "Brasil"].compact.join(', ')
    ap [zipcode, "Brasil"].compact.join(', ')
  end
end
