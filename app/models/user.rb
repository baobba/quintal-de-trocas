class User < ActiveRecord::Base
  acts_as_messageable
  acts_as_paranoid
  
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :trackable, :validatable, :confirmable

  validates :name, :email, :birthday, :gender, presence: true
  validates :zipcode, :phone, :street, :neighborhood, :city, :state, presence: true, if: :not_recovering_password?

  has_many :places
  has_many :items
  has_many :exchanges
  has_many :exchanges_received, :class_name => 'Exchange', :foreign_key => "user_to"
  has_many :credits
  has_many :credits_avail, -> (object){ where(expired_at: nil)}, :class_name => 'Credit'
  has_many :orders
  has_many :sales, :class_name => 'Order', :foreign_key => "seller_id"
  has_one :store

  has_many :user_children

  accepts_nested_attributes_for :user_children, 
    allow_destroy: true, 
    reject_if: :all_blank

  mount_uploader :avatar, UserUploader

  geocoded_by :zipcode

  # geocoded_by :street, :city, :state, :zipcode
  after_validation :geocode
  after_create :send_slack_message

  def send_slack_message
    message = "#{self.name} (#{self.email}), acabou de se cadasatrar."
    NOTIFIER.ping(message)
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

  def not_recovering_password?
    password_confirmation.nil?
  end

  def store?
    store ? true : false
  end

  def full_address
    [street, complement, neighborhood, city, state].join(", ")
  end

  def confirm!
    welcome_email
    super
  end

  private
  def welcome_email
    QuintalMailer.welcome_message(self).deliver
  end

end
