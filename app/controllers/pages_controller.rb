class PagesController < ActionController::Base
  layout 'application'

  def about_us
  end

  def support
  end

  def how_it_works
  end

  def partners
  end

  def testimonials
  end

  def media
  end

  def faq
  end

  def contact_us
    @message = Contact.new
  end

  def busca_por_cep
    render :json => BuscaEndereco.cep(params[:cep])
  rescue RuntimeError
    render :json => ["", "zipcode nao encontrado", "", "", ""].to_json
  end

end
