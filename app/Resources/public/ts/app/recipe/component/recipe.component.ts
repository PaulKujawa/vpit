import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Params} from "@angular/router";
import {RecipeRepository} from "../repository/recipe.repository";
import {Recipe} from "../model/recipe";
import {FlashMessageService} from "../../core/service/flash-message.service";
import {FlashMessage} from "../../core/model/flash-message";
import {Ingredient} from "../model/ingredient";

@Component({
    selector: 'recipe-detail',
    template: `
        <h1 class="text-center">{{ recipe?.name }}</h1>
        
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <img class="img-circle center-block img-responsive" [src]="getImageUrl()">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-5">
                <h2>{{ 'app.recipe.ingredients'|trans }}</h2>
                <table class="table">
                    <tbody>
                        <tr *ngFor="let ingredient of recipe?.ingredients">
                            <th scope="row">{{ getMeasurement(ingredient) }}</th>                
                            <td>{{ ingredient.product.name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-12 col-sm-7 col-md-6 col-md-offset-1">
                <h2>{{ 'app.recipe.cooking'|trans }}</h2>
                <ul class="list-group">
                    <li class="list-group-item" *ngFor="let cooking of recipe?.cookings">
                        {{ cooking.description }}
                    </li>
                </ul>
            </div>
        </div>
    `
})
export class RecipeComponent implements OnInit {
    recipe: Recipe;

    constructor(private recipeRepository: RecipeRepository,
                private activatedRoute: ActivatedRoute,
                private flashMsgService: FlashMessageService) {}


    ngOnInit(): void {
        this.activatedRoute.params.forEach((params: Params) => {
            this.getRecipe(+params['id']);
        });

    }

    getMeasurement(ingredient: Ingredient): string {
        return ingredient.amount
            ? [ingredient.amount, ingredient.measurement.name].join(' ')
            : null;
    }

    getRecipe(id: number): void {
        this.recipeRepository.getRecipe(id)
            .subscribe(
                (recipe: Recipe) => this.recipe = recipe,
                (error: string) => this.flashMsgService.push(new FlashMessage('danger', error))
            );
    }

    getImageUrl(): string {
        return this.recipe && this.recipe.thumbnail
            ? this.recipe.thumbnail.path
            : 'http://placehold.it/400x400';
    }
}
