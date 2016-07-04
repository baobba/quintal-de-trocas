require 'test_helper'

class ToyCategoriesControllerTest < ActionController::TestCase
  setup do
    @toy_category = toy_categories(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:toy_categories)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create toy_category" do
    assert_difference('ToyCategory.count') do
      post :create, toy_category: { title: @toy_category.title }
    end

    assert_redirected_to toy_category_path(assigns(:toy_category))
  end

  test "should show toy_category" do
    get :show, id: @toy_category
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @toy_category
    assert_response :success
  end

  test "should update toy_category" do
    patch :update, id: @toy_category, toy_category: { title: @toy_category.title }
    assert_redirected_to toy_category_path(assigns(:toy_category))
  end

  test "should destroy toy_category" do
    assert_difference('ToyCategory.count', -1) do
      delete :destroy, id: @toy_category
    end

    assert_redirected_to toy_categories_path
  end
end
