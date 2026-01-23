@props(['snacc'])

@if($snacc->content)
    @php
        $content = $snacc->content;
        $content = preg_replace_callback(
            '/~(\w+)/',
            function($matches) {
                $tag = $matches[1];
                $url = route('search', ['q' => $tag, 'type' => 'posts']);
                return '<a href="' . $url . '" class="text-primary-500 dark:text-primary-400 hover:underline font-medium">~' . $tag . '</a>';
            },
            htmlspecialchars($content, ENT_QUOTES, 'UTF-8')
        );
    @endphp
    <p class="text-[15px] text-gray-900 dark:text-gray-100 whitespace-pre-wrap break-words leading-normal">{!! $content !!}</p>
@endif
