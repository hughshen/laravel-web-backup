<?php

namespace App\Repositories;

use App\Models\Term;
use Bosnadev\Repositories\Eloquent\Repository;

class TermRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Term::class;
    }

    /**
     * Save new term
     *
     * @return bool
     */
    public function saveNewTerm($name, $description, $slug, $taxonomy, $status)
    {
        $term = factory(Term::class)->create([
            'name' => $name,
            'description' => $description,
            'slug' => $slug,
            'taxonomy' => $taxonomy,
            'status' => $status,
        ]);

        return $term;
    }

    /**
     * Update term by id
     *
     * @return bool
     */
    public function updateTermById($id, $name, $description, $slug, $taxonomy, $status)
    {
        $term = Term::findOrFail($id);

        return $term->update([
            'name' => $name,
            'description' => $description,
            'slug' => $slug,
            'taxonomy' => $taxonomy,
            'status' => $status,
            'updated_at' => time(),
        ]);
    }

    /**
     * Destroy term by id
     *
     * @param integer $id
     * @return bool
     */
    public function destroyTermById($id)
    {
        $term = Term::findOrFail($id);

        app('db')->beginTransaction();
        try {
            $term->delete();
            $term->posts()->detach();

            app('db')->commit();

            return true;
        } catch (\Exception $e) {
            app('db')->rollBack();
            return false;
        }
    }

    /**
     * Get term by id
     *
     * @param integer $id
     * @return \Model
     */
    public function getTermById($id)
    {
        $term = Term::findOrFail($id);

        return $term;
    }

    /**
     * Index builder
     *
     * @param null $tax
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getIndexBuilder($tax = null)
    {
        $builder = Term::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($tax) {
            $builder->where('taxonomy', $tax);
        }

        return $builder;
    }

    /**
     * Get terms paginate
     *
     * @param string $tax
     * @param integer $limit
     * @return mixed
     */
    public function getTermsPaginate($tax = null, $limit = 10)
    {
        return $this->getIndexBuilder($tax)->paginate($limit);
    }

    /**
     * Get all terms
     *
     * @param string $tax
     * @return mixed
     */
    public function getAllTerms($tax = null)
    {
        return $this->getIndexBuilder($tax)->get();
    }

    /**
     * Get site all terms by taxonomy
     *
     * @param string $tax
     * @param integer $limit
     * @return mixed
     */
    public static function getSiteAllTerms($tax, $limit = 10)
    {
        return Term::query()
            ->where('taxonomy', $tax)
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Get site term by slug and taxonomy
     *
     * @param $slug
     * @param $tax
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function getSiteTermBySlug($slug, $tax)
    {
        return Term::query()
            ->where('taxonomy', $tax)
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();
    }
}