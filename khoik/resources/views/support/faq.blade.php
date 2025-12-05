<x-layout title="Câu hỏi thường gặp - FAQ">
    <x-page-header 
        title="❓ Câu hỏi thường gặp (FAQ)" 
        subtitle="Tìm câu trả lời nhanh chóng cho các thắc mắc của bạn"
    />

    <div class="max-w-4xl mx-auto">
        @foreach($faqs as $category)
        <x-card class="mb-6">
            <x-slot:header>
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <span class="text-2xl">{{ $category['icon'] }}</span>
                    {{ $category['category'] }}
                </h3>
            </x-slot:header>

            <div class="space-y-4">
                @foreach($category['questions'] as $index => $item)
                <div class="border-b last:border-0 pb-4 last:pb-0">
                    <button class="w-full text-left font-semibold text-gray-900 flex justify-between items-start gap-4 group" 
                            onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="flex-1">{{ $item['q'] }}</span>
                        <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div class="hidden mt-2 text-gray-600 pl-4 border-l-2 border-orange-200">
                        {{ $item['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </x-card>
        @endforeach

        <!-- CTA -->
        <div class="info-box info-box-primary text-center">
            <p class="font-semibold mb-3">Không tìm thấy câu trả lời?</p>
            <x-button :href="route('support.contact')" variant="primary">
                📧 Liên hệ với chúng tôi
            </x-button>
        </div>
    </div>
</x-layout>
