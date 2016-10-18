class ExchangeMessage < ActiveRecord::Base
  belongs_to :user, :foreign_key => "user_from"
  belongs_to :exchange

  def from_user
    self.user
  end

  def to_user
    User.with_deleted.find_by_id(user_to)
  end
end
