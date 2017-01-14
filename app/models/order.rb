class Order < ActiveRecord::Base
  belongs_to :user
<<<<<<< HEAD
  belongs_to :toy
=======
  belongs_to :item
>>>>>>> cf00e2e780b10aa9b0512eb642e00095b51aac69
  # attr_accessible :code, :price, :title, :status

  before_save :normalize_status

  def normalize_status
    # ap "----"
    # ap self
    # ap self.status
    # ap self.status.class
    # ap "----"

    if self.status
      # ap "passou"
      self.status = if self.status == "1"
        "Aguardando pagamento"
      elsif self.status == "2"
        "Em análise"
      elsif self.status == "3"
        "Paga"
      elsif self.status == "4"
        "Paga"
      elsif self.status == "5"
        "Em disputa"
      elsif self.status == "6"
        "Devolvida"
      elsif self.status == "7"
        "Cancelada"
      elsif self.status == "8"
        "Chargeback debitado"
      elsif self.status == "9"
        "Em contestação"
      elsif self.status == "10"
        "Em devolução"
      else
        "???"
      end
    end
  end

end
