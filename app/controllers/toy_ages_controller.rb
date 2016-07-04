class ToyAgesController < InheritedResources::Base

  private

    def toy_age_params
      params.require(:toy_age).permit(:title)
    end
end

