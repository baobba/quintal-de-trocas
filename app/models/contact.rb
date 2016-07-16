class Contact

  include ActiveModel::Model
  include ActiveModel::Conversion
  include ActiveModel::Validations

  attr_accessor :name, :email, :city, :state, :subject, :message

  validates :name,
    presence: true

  validates :email,
    presence: true

  validates :message,
    presence: true

end
