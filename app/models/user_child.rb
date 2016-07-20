class UserChild < ActiveRecord::Base
  belongs_to :user

  validates :name, :birthday, presence: true
end
