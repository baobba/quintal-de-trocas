class UserChild < ActiveRecord::Base
  belongs_to :user

  validates :name, :birthday, :gender, presence: true
end
