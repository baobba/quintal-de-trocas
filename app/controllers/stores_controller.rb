class StoresController < InheritedResources::Base
  before_action :authenticate_user!

  def new
    @store = current_user.build_store
  end

  def create
    @store = current_user.build_store(store_params)
    ap @store

    respond_to do |format|
      if @store.save
        ap @store
        format.html { redirect_to root_path, success: 'Loja cadastrada com sucesso' }
        format.json { render :show, status: :created, location: @store }
      else
        format.html { render :new }
        format.json { render json: @store.errors, status: :unprocessable_entity }
      end
    end
  end

  private

    def store_params
      params.require(:store).permit(:name, :user_id)
    end
end

