@extends('layout')
@section('title', 'Product Category')
@section('nav-title', 'Product Category')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium">Product Category List</h1>
                <button id="addModalBtn" data-modal-target="productcategory-modal" data-modal-toggle="productcategory-modal"
                    class="px-5 py-3 font-semibold text-white rounded-full bg-primary">Add New</button>
        </div>
        @php
            $headers = ['ID', 'Image', 'Name', 'No. of Sub Category', 'No. of Products', 'Status', 'Action'];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">

                <tr>
                    <td>1</td>
                    <td>
                        <img class="rounded-full w-11 h-11" src="{{ asset('asset/Ellipse 2.png') }}" alt="Jese image">
                    </td>
                    <td>Clothes</td>
                    <td>50</td>
                    <td>4</td>
                    <td><span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Approved</span></td>
                    <td>
                        <span class='flex gap-4'>
                            <button class="updateDataBtn">
                                <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                    xmlns='http://www.w3.org/2000/svg'>
                                    <circle opacity='0.1' cx='18' cy='18' r='18' fill='#233A85' />
                                    <path fill-rule='evenodd' clip-rule='evenodd'
                                        d='M16.1637 23.6188L22.3141 15.665C22.6484 15.2361 22.7673 14.7402 22.6558 14.2353C22.5593 13.7763 22.277 13.3399 21.8536 13.0088L20.8211 12.1886C19.9223 11.4737 18.8081 11.549 18.1693 12.3692L17.4784 13.2654C17.3893 13.3775 17.4116 13.543 17.523 13.6333C17.523 13.6333 19.2686 15.0329 19.3058 15.063C19.4246 15.1759 19.5137 15.3264 19.536 15.507C19.5732 15.8607 19.328 16.1918 18.9641 16.2369C18.7932 16.2595 18.6298 16.2068 18.511 16.109L16.6762 14.6492C16.5871 14.5822 16.4534 14.5965 16.3791 14.6868L12.0188 20.3304C11.7365 20.6841 11.64 21.1431 11.7365 21.5871L12.2936 24.0025C12.3233 24.1304 12.4348 24.2207 12.5685 24.2207L15.0197 24.1906C15.4654 24.1831 15.8814 23.9799 16.1637 23.6188ZM19.5958 22.8672H23.5929C23.9829 22.8672 24.3 23.1885 24.3 23.5835C24.3 23.9794 23.9829 24.2999 23.5929 24.2999H19.5958C19.2059 24.2999 18.8887 23.9794 18.8887 23.5835C18.8887 23.1885 19.2059 22.8672 19.5958 22.8672Z'
                                        fill='#233A85' />
                                </svg>
                            </button>
                            <button class="deleteDataBtn" delId="" delUrl="" name="media_id">
                                <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                    xmlns='http://www.w3.org/2000/svg'>
                                    <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                    <path fill-rule='evenodd' clip-rule='evenodd'
                                        d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                        fill='#D11A2A' />
                                </svg>
                            </button>

                            <button class="viewModalBtn">
                                <svg width='37' height='36' viewBox='0 0 37 36' fill='none'
                                    xmlns='http://www.w3.org/2000/svg'>
                                    <path fill-rule='evenodd' clip-rule='evenodd'
                                        d='M28.0642 18.5C28.0642 18.126 27.8621 17.8812 27.4579 17.3896C25.9788 15.5938 22.7163 12.25 18.9288 12.25C15.1413 12.25 11.8788 15.5938 10.3996 17.3896C9.99542 17.8812 9.79333 18.126 9.79333 18.5C9.79333 18.874 9.99542 19.1187 10.3996 19.6104C11.8788 21.4062 15.1413 24.75 18.9288 24.75C22.7163 24.75 25.9788 21.4062 27.4579 19.6104C27.8621 19.1187 28.0642 18.874 28.0642 18.5ZM18.9288 21.625C19.7576 21.625 20.5524 21.2958 21.1385 20.7097C21.7245 20.1237 22.0538 19.3288 22.0538 18.5C22.0538 17.6712 21.7245 16.8763 21.1385 16.2903C20.5524 15.7042 19.7576 15.375 18.9288 15.375C18.0999 15.375 17.3051 15.7042 16.719 16.2903C16.133 16.8763 15.8038 17.6712 15.8038 18.5C15.8038 19.3288 16.133 20.1237 16.719 20.7097C17.3051 21.2958 18.0999 21.625 18.9288 21.625Z'
                                        fill='url(#paint0_linear_872_5570)' />
                                    <circle opacity='0.1' cx='18.4287' cy='18' r='18'
                                        fill='url(#paint1_linear_872_5570)' />
                                    <defs>
                                        <linearGradient id='paint0_linear_872_5570' x1='18.9288' y1='12.25'
                                            x2='18.9288' y2='24.75' gradientUnits='userSpaceOnUse'>
                                            <stop stop-color='#FCB376' />
                                            <stop offset='1' stop-color='#FE8A29' />
                                        </linearGradient>
                                        <linearGradient id='paint1_linear_872_5570' x1='18.4287' y1='0'
                                            x2='18.4287' y2='36' gradientUnits='userSpaceOnUse'>
                                            <stop stop-color='#FCB376' />
                                            <stop offset='1' stop-color='#FE8A29' />F
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </button>
                        </span>
                    </td>
                </tr>
            </x-slot>
        </x-table>

        <x-modal id="productcategory-modal">
            <x-slot name="title">Details</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <!-- Category Name -->
                    <div>
                        <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" id="category_name" name="category_name"
                            class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <!-- Category Image -->
                    <div>
                        <label for="category_image" class="block text-sm font-medium text-gray-700">Category Image</label>
                        <input type="file" id="category_image" name="category_image" accept="image/*"
                            class="mt-1 p-2 w-full border rounded-lg">
                    </div>

                    <!-- Subcategories Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sub Categories</label>
                        <div id="subcategories">
                            <div class="flex gap-2 mt-2 subcategory-item">
                                <input type="text" name="subcategories[]" class="p-2 w-full border rounded-lg"
                                    placeholder="Subcategory Name">
                                <button type="button"
                                    class="bg-red-500 text-white px-3 py-1 rounded-lg remove-subcategory hidden">X</button>
                            </div>
                        </div>
                        <button type="button" id="add-subcategory"
                            class="mt-2 bg-blue-500 text-white px-3 py-2 rounded-lg">+ Add Subcategory</button>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full bg-green-500 text-white p-3 rounded-lg">Submit</button>
                    </div>
                </form>

                <!-- JavaScript for Dynamic Subcategories -->
                <script>
                    document.getElementById('add-subcategory').addEventListener('click', function() {
                        let subcategoryContainer = document.getElementById('subcategories');

                        let subcategoryDiv = document.createElement('div');
                        subcategoryDiv.classList.add('flex', 'gap-2', 'mt-2', 'subcategory-item');

                        subcategoryDiv.innerHTML = `
                            <input type="text" name="subcategories[]" class="p-2 w-full border rounded-lg" placeholder="Subcategory Name">
                            <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-lg remove-subcategory">X</button>
                        `;

                        subcategoryContainer.appendChild(subcategoryDiv);

                        // Add event listener to new remove button
                        subcategoryDiv.querySelector('.remove-subcategory').addEventListener('click', function() {
                            subcategoryDiv.remove();
                        });

                        // Show remove button for all subcategories
                        document.querySelectorAll('.remove-subcategory').forEach(button => button.classList.remove('hidden'));
                    });

                    // Initial remove button should be hidden if only one subcategory exists
                    document.querySelector('.remove-subcategory').addEventListener('click', function() {
                        this.parentElement.remove();
                    });
                </script>
            </x-slot>

        </x-modal>
    </div>


@endsection
