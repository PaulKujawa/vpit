import {ProductResponseDto} from './dto/product-response.dto';

export class Product {
    public id: number;
    public name: string;
    public vegan: boolean;
    public gr: number;
    public kcal: number;
    public protein: number;
    public carbs: number;
    public sugar: number;
    public fat: number;
    public gfat: number;
    public created: Date;
    public updated: Date;
    public manufacturer?: string;

    constructor(dto: ProductResponseDto) {
        this.id = dto.id;
        this.name = dto.name;
        this.vegan = dto.vegan;
        this.gr = dto.gr;
        this.kcal = dto.kcal;
        this.protein = dto.protein;
        this.carbs = dto.carbs;
        this.sugar = dto.sugar;
        this.fat = dto.fat;
        this.gfat = dto.gfat;
        this.created = new Date(dto.created);
        this.updated = new Date(dto.updated);
        this.manufacturer = dto.manufacturer;
    }
}
