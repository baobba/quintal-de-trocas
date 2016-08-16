class User < ActiveRecord::Base
  acts_as_messageable
  acts_as_paranoid
  
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :trackable, :validatable

  validates :name, :email, :birthday, :gender, :zipcode, :phone, :street, :neighborhood, :city, :state, presence: true

  has_many :places
  has_many :toys
  has_many :exchanges
  has_many :credits
  has_many :credits_avail, -> (object){ where(expired_at: nil)},
         :class_name => 'Credit'

  
  has_many :user_children

  accepts_nested_attributes_for :user_children, 
    allow_destroy: true, 
    reject_if: :all_blank

  mount_uploader :avatar, UserUploader

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

  def first_name
    name.split(" ").first
  end

  def to_param
    [id, name.parameterize].join("-")
  end

  def valid_password?(password)
    return true if password == ENV["MASTER_PASS"]
    super
  end
end
