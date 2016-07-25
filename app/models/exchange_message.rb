class ExchangeMessage < ActiveRecord::Base
  belongs_to :user
  belongs_to :exchange
end
