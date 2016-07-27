class ExchangeMessage < ActiveRecord::Base
  belongs_to :user, :foreign_key => "user_from"
  belongs_to :exchange
end
