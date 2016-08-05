class ErrorsController < ApplicationController

  def not_found
    render text: '', layout: 'errors', status: 404
  end

  def internal_server_error
    render text: '', layout: 'errors', status: 500
  end
end