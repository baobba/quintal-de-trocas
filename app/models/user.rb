class User < ActiveRecord::Base
  acts_as_messageable
  
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :trackable, :validatable


  has_many :places
  has_many :toys
  has_many :exchanges

  geocoded_by :zipcode

  # geocoded_by :street, :city, :state, :zipcode
  after_validation :geocode

  def mailboxer_email(object)
    #Check if an email should be sent for that object
    #if true
    return "netto16@gmail.com"
    #if false
    #return nil
  end

  def to_param
    [id, name.parameterize].join("-")
  end
end
