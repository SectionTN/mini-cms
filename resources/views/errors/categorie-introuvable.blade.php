<x-layout>
    <x-slot:title>Catégorie introuvable - Mini-CMS</x-slot:title>

    <x-page-header title="Catégorie introuvable" subtitle="Cette catégorie n'existe pas ou a été supprimée." />

    <x-alert type="info" title="Que faire ?" class="max-w-2xl">
        Choisissez une des catégories ci-dessous, ou parcourez la liste des articles.
    </x-alert>

    @if ($categories->isNotEmpty())
        <section class="mt-8" aria-labelledby="categories-title">
            <h2 id="categories-title" class="text-xl font-bold mb-4">Catégories</h2>

            <ul class="flex flex-wrap gap-2">
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('posts.category', $category) }}" class="inline-block rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700 hover:bg-indigo-100">{{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    <p class="mt-8">
        <a href="{{ route('posts.index') }}" class="font-medium text-indigo-600 hover:underline">Voir tous les articles</a>
    </p>
</x-layout>
