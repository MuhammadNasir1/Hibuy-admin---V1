<div class="relative flex items-center justify-center w-full h-full">
    <label
        class="flex flex-col items-center justify-center w-full h-full bg-gray-300 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer file-upload-label">
        <div class="flex flex-col items-center justify-center pt-5 pb-6 file-upload-content">
            <svg width="34" height="26" viewBox="0 0 34 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M27.2551 9.98342C26.3185 4.55652 22.1455 0.482422 17.1323 0.482422C13.1521 0.482422 9.69525 3.06216 7.9737 6.83739C3.82821 7.34075 0.605469 11.3519 0.605469 16.2125C0.605469 21.4192 4.31024 25.6506 8.86891 25.6506H26.773C30.5742 25.6506 33.6592 22.1271 33.6592 17.7856C33.6592 13.6328 30.8359 10.2666 27.2551 9.98342ZM19.8868 14.6395V20.9316H14.3779V14.6395H10.2461L17.1323 6.77447L24.0185 14.6395H19.8868Z" fill="#4B91E1"/>
                </svg>

            <p class="mb-2 text-sm text-customblue dark:text-gray-400"><span class="font-semibold">upload</span>
            </p>
        </div>
        <input type="file" class="hidden file-input" name="{{ $name }}" accept="image/*"
            onchange="previewFile(event)" />
        <img class="absolute top-0 left-0 hidden object-contain w-full h-full rounded-lg file-preview bg-customOrangeDark" />
    </label>
</div>
